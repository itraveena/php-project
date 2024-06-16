<?php

namespace App\Http\Controllers;

use App\Contracts\GetPaymentMethodsInputs;
use App\Interfaces\IPaymentService;
use App\Libraries\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Exception;
class PaymentController extends Controller
{
    public function __construct(private IPaymentService $service){}

    public function getPaymentMethods()
    {
        try {
            // validate request and inputs ( ideally use laravel function to create a new request type)
            // convert Request to array and use to create GetPaymentMethodInputs
            $inputs = new GetPaymentMethodsInputs($_REQUEST);
            $paymentMethodData = $this->service->GetPaymentMethods($inputs);
            return ApiResponse::successMessage($paymentMethodData);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }

    public function getFranchisePaymentMethods(){
        try {
            $inputs = new GetPaymentMethodsInputs($_REQUEST);
            $paymentMethodData = $this->service->GetFranchisePaymentMethods($inputs);
            return ApiResponse::successMessage($paymentMethodData);
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::failedMessage(CommonHelper::exceptionError($exception));
        }
    }
}