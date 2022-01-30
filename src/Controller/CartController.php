<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\Exception\InvalidEntityRepository;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\OutOfBoundsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\Translation\t;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private SessionInterface $session;
    private string $sessionName;

    public function __construct(private RequestStack $requestStack,
                                string $sessionName)
    {
        $this->session = $this->requestStack->getSession();
        $this->sessionName = $sessionName;
    }

    #[Route('/display', name: 'display')]
    public function displayCart(): Response
    {
        $cart = null;
        if(!empty($this->session->get($this->sessionName, []))) {
            $cart = $this->session->get($this->sessionName);
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $cart
        ]);
    }

    #[Route('/add/{slug}', name: 'add_product')]
    public function addToCart(TranslatorInterface $translator, Request $request, Product $product): Response
    {
        $quantity = $request->query->get('quantity') !== null ?
            $request->query->get('quantity') : 1;

        if($quantity > 999) {
            throw new OutOfBoundsException(
                $translator->trans('cart.error.quantity-out-of-bound', [], 'cart')
            );
        }

        $cart = !empty($this->session->get($this->sessionName, [])) ?
            $this->session->get($this->sessionName) : new Cart();

        $this->updateCart($product, $cart, $quantity);
        $this->updateSession($cart);
        $this->addFlash('success', $translator->trans('cart.add.success', [], 'cart'));

        return $this->redirectToRoute('product_list');
    }

    #[Route('/refresh', name: 'refresh')]
    public function refreshCart(Request $request,
                                ProductService $productService,
                                TranslatorInterface $translator): JsonResponse
    {
        $productSlug = $request->get('productSlug');
        $quantity = $request->get('quantity');

        $criteria = [
            'attribute' => 'slug',
            'value' => $productSlug
        ];
        $product = $productService->getProductBy($criteria);
        if(null === $product) {
            return new JsonResponse([
                'error' => false,
                'message' => $translator->trans('cart.refresh.error', [], 'cart'),
            ]);
        }

        $cart = $this->getSessionCart();
        if(!$cart instanceof Cart) {
            return new JsonResponse([
                'error' => false,
                'message' => $translator->trans('cart.refresh.error', [], 'cart'),
            ]);
        }

        $key = $this->getProductLineInCart($cart, $product);

        $productLine = $cart->getProductLines()[$key];
        $productLine['quantity'] = $quantity;

        $cart->updateProductLine($productLine, $key);
        $this->updateSession($cart);

        return new JsonResponse([
            'error' => false,
            'message' => $translator->trans('cart.refresh.success', [], 'cart'),
            'total' => $cart->getTotal()
        ]);
    }

    #[Route('/remove/{slug}', name: 'remove_line')]
    public function removeProductLine(Product $product, TranslatorInterface $translator): RedirectResponse
    {
        $cart = $this->getSessionCart();
        if(!$cart instanceof Cart) {
            throw new \InvalidArgumentException($translator->trans('cart.remove.error', [], 'cart'));
        }

        $key = $this->getProductLineInCart($cart, $product);

        $cart->removeProductLine($key);
        $this->updateSession($cart);

        $this->addFlash('success', $translator->trans('cart.remove.success', [], 'cart'));
        return $this->redirectToRoute('cart_display');
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkOut(TranslatorInterface $translator): RedirectResponse
    {
        $this->session->remove($this->sessionName);
        $this->addFlash('success', $translator->trans('cart.checkout', [], 'cart'));

        return $this->redirectToRoute('home');
    }

    private function updateCart(Product $product, Cart $cart, int $quantity): void
    {
        $productInCart = array_filter($cart->getProductLines(), function ($line) use ($product) {
            return $line['id'] === $product->getId();
        });

        if (empty($productInCart)) {
            $cart->addProductLine($product, $quantity);
        } else {
            $keys = array_keys($productInCart);
            $key = $keys[0];

            $productLine = $cart->getProductLines()[$key];
            $productLine['quantity'] += $quantity;

            $cart->updateProductLine($productLine, $key);
        }

        $cart->setTotal();
    }

    private function updateSession(mixed $cart): void
    {
        $this->session->set($this->sessionName, $cart);
    }

    private function getSessionCart(): Cart
    {
        return $this->session->get($this->sessionName, []);
    }

    private function getProductLineInCart(Cart $cart, Product $product): string|int
    {
        $productInCart = array_filter($cart->getProductLines(), function ($line) use ($product) {
            return $line['id'] === $product->getId();
        });

        $keys = array_keys($productInCart);
        $key = $keys[0];
        return $key;
    }
}
