<?php

namespace App\Http\Controllers;

use App\Interfaces\CartServiceInterface;
use App\Libraries\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Exception;

class PAIController extends Controller
{


    public function __construct(private CartServiceInterface $cartService)
    {

    }

    public function getAddPAI(Request $request)
    {
        try {
            $response = $this->cartService->addPAI($request);
            return ApiResponse::successMessage($response);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }
    public function getRemovePAI(Request $request)
    {
        try {
            $response = $this->cartService->removePai($request);
            return ApiResponse::successMessage($response);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

}
