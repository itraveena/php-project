<?php

namespace App\Http\Controllers;

use App\Interfaces\CartServiceInterface;
use App\Libraries\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Exception;

class GiftCardController extends Controller
{

    private $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    public function applyGiftCard()
    {
        try {
            $giftCardID = request('id');
            $response = $this->cartService->applyGiftCard($giftCardID);

            return ApiResponse::successMessage($response);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }

    }

    public function removeGiftCard()
    {
        try {
            $response = $this->cartService->removeGiftCard();

            return ApiResponse::successMessage($response);
        } catch (\Throwable $throwable) {
            return ApiResponse::failedMessage($throwable->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

}
