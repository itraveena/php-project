<?php

namespace App\Http\Controllers;



use App\Interfaces\CartServiceInterface;
use App\Libraries\ApiResponse;
use App\Entities\Cart\AddCartAddressInputs;
use App\Entities\Cart\AddItemInput;
use App\Entities\Cart\GetCartAddressesInputs;
use App\Entities\Cart\RemoveCartAddressInputs;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Exceptions\ValidationException;
use Exception;

class CartController extends Controller
{

    private $cartService;
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart()
    {
        try {
    
            return $this->cartService->getCart();
        } catch (ValidationException $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function ping()
    {
        try {
            return ApiResponse::successMessage("Pong");
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));    
        }
    }

    public function addToCart(Request $request)
    {
        //id or product_title are utmost necessary params
        try {
            
            if( empty($request->get("id")) && empty($request->get("product_title")) ){
                throw new ValidationException('Kindly provide valid listing id or product title to add to cart');
            }
           $allinputs =  $this->allInputsAssign();            

            $cart = $this->cartService->addItem($allinputs);

            return ApiResponse::successMessage($cart);
        } catch (ValidationException $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function addMultipleItemsToCart(Request $request)
    {
        //id or product_title are utmost necessary params
        try {
            $products = $request->get("product");
            if(empty($products)){
                throw new ValidationException('Kindly provide valid listing ids to add to cart');
            }
            $inputs = json_decode(json_encode($products), FALSE);
            $addMultipleItemsInput = array(
                'express' => $request->get("express") ?? '',
                'address_id' => $request->get("address_id") ?? '',
                'cart_reset' => $request->get("cart_reset") ?? '',
                'redirect_url' => $request->get("redirect_url") ?? '',
                'products' => $inputs
            );
            $cart = $this->cartService->addMultipleItems($addMultipleItemsInput);

            return ApiResponse::successMessage($cart);
        } catch (ValidationException $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }



    /**  
     * UpdateAddress saves provided address, applied it to cart and returns collection of all user addresses along with updated cart
     */

    public function updateAddress(Request $request)
    {
        try {
            $inputs =  new AddCartAddressInputs();
            $inputs->id = $request->get("id");
            $inputs->name = $request->get("name");
            $inputs->phone = $request->get("phone");
            $inputs->pincode = $request->get("pincode");
            $inputs->address = $request->get("address");
            $inputs->landmark = $request->get("landmark");
            $result =  $this->cartService->updateAddress($inputs);

            return ApiResponse::successMessage($result);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function removeAddress(Request $request)
    {
        try {
            $inputs =  new RemoveCartAddressInputs();
            $inputs->id = $request->get("address_id");

            $result =  $this->cartService->removeAddress($inputs);
            return ApiResponse::successMessage($result);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function getAddressesWithCart(Request $request)
    {
        try {
            $input = new GetCartAddressesInputs();
            $input->userID = $request->get("user_id");
            $input->addressId = $request->get("address_id");
            $result =  $this->cartService->getCartAddressesWithAppliedAddress($input);
            return ApiResponse::successMessage($result);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function getAddresses(Request $request)
    {
        try {
            $inputs =  new GetCartAddressesInputs();
            $inputs->userID = $request->get("user_id");
            $result = $this->cartService->getCartAddresses($inputs);
            return ApiResponse::successMessage($result);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function addAddress(Request $request)
    {
        try {
            $inputs =  new AddCartAddressInputs();
            $inputs->id = (int) $request->get("id");
            $inputs->name = $request->get("name");
            $inputs->phone = $request->get("phone");
            $inputs->pincode = $request->get("pincode");
            $inputs->address = $request->get("address");
            $inputs->landmark = $request->get("landmark");
            $result =  $this->cartService->addAddress($inputs);
            return ApiResponse::successMessage($result);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }
    public function resetCart(Request $request)
    {
        try {

            $cart = $this->cartService->resetCart($request);
            return ApiResponse::successMessage($cart);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    private function allInputsAssign(){
        $request = request();
        $inputs = new AddItemInput();
        $inputs->id = $request->get("id");
        $inputs->product_title = $request->get("product_title");
        $inputs->cart_reset = $request->get("cart_reset");
        $inputs->express = $request->get("express");
        $inputs->address_id = $request->get("address_id");
        $inputs->quantity = $request->get("quantity");
        $inputs->order_type = $request->get("order_type");
        $inputs->extend_bs = $request->get("extend_bs");
        $inputs->package_name = $request->get("package_name");
        $inputs->selling_price = $request->get("selling_price");
        $inputs->category_id = $request->get("category_id");
        $inputs->vehicle_type = $request->get("vehicle_type");
        $inputs->seller_id = $request->get("seller_id");
        $inputs->seller_name = $request->get("seller_name");
        $inputs->cart_image_url = $request->get("cart_image_url");
        $inputs->shipping_charges = $request->get("shipping_charges");
        $inputs->equipment_costing = $request->get("equipment_costing");
        $inputs->equipment_cost_type = $request->get("equipment_cost_type");
        $inputs->vehicle_registration_no = $request->get("vehicle_registration_no");
        $inputs->reference_id = $request->get("reference_id");
        $inputs->order_against_listing = $request->get("order_against_listing");
        $inputs->order_against_listing_by_buyer = $request->get("order_against_listing_by_buyer");
        $inputs->report_type = $request->get("report_type");
        $inputs->report_source = $request->get("report_source");
        $inputs->registration_number = $request->get("registration_number");
        $inputs->quicksell_price = $request->get("quicksell_price");
        $inputs->home_delivery_available = $request->get("home_delivery_available");
        $inputs->built_up_area = $request->get("built_up_area");
        $inputs->swab_test_price = $request->get("swab_test_price");
        $inputs->total_amount = $request->get("total_amount");
        $inputs->user_mail = $request->get("user_mail");
        $inputs->message = $request->get("message");
        $inputs->source = $request->get("source");
        $inputs->token_financing = $request->get("token_financing");
        $inputs->pay_via_token_financing = $request->get("pay_via_token_financing");
        $inputs->redirect_url = $request->get("redirect_url");
        $inputs->transfer_from_opid = $request->get("transfer_from_opid");
        $inputs->user_inputs = $request->get("user_inputs") ?? [];
        
        return $inputs;
    }
    
    public function removeItem(Request $request)
    {
        try {
            $itemKey = $request->get('item_key');

            $cart = $this->cartService->removeItem($itemKey);
        } catch (Exception $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        }
    }
    
    public function updateQuantity(Request $request){
       
        $item_key = $request->get('item_key');
        $quantity= $request->get('quantity');
        $input = array('item_key'=>$item_key ,'quantity'=>$quantity); 
        $cart= $this->cartService->updateItemQuantity($input);
        return $cart;
    }

    public function moveToWishlist(Request $request){
        $input =$request->get('item_key');
        $cart = $this->cartService->moveToWishlist($input);
        return $cart;
    }
}
