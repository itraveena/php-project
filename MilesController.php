<?php

namespace App\Http\Controllers;

use App\Interfaces\CartServiceInterface;
use App\Libraries\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Exception;

class MilesController extends Controller
{

    public function __construct(private CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    
    public function applyMiles(Request $request)
    {
        try {
            $response = $this->cartService->applyMiles($request);

            return ApiResponse::successMessage($response);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function removeMiles(Request $request)
    {
        try {
            $response = $this->cartService->removeMiles($request);

            return ApiResponse::successMessage($response);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }
}
