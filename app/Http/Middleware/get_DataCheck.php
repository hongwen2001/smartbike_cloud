<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class get_DataCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {//        判斷資料是否正確，回傳結果(result)和訊息(Message)，當結果為true時Message帶
        $validatorway=Validator::make($request->toArray(),['id'=>['required'],'Way'=>['required']]);
        if ($validatorway->passes()){
            if ($request->Way=='order') {
                $validatorcontent = Validator::make($request->toArray(), ['InitialPosition'=>['required'],'Quantity'=>['required']]);
                return $validatorcontent->passes()?$next($request):response()->json(['status' => 1, 'message' => "InitialPosition or Quantity error"]);;
            }
            elseif ($request->Way=='date') {
                $validatorcontent = Validator::make($request->toArray(), ['DataTime'=>['required','date_format:Y-m-d']]);
                return $validatorcontent->passes()?$next($request):response()->json(['status' => 1, 'message' => "DataTime error"]);;

            }
            elseif ($request->Way=='datetime') {
                $validatorcontent = Validator::make($request->toArray(), ['DataTime'=>['required','date_format:Y-m-d H:i:s']]);
                return $validatorcontent->passes()?$next($request):response()->json(['status' => 1, 'message' => "DataTime error"]);;

            }else{
                return response()->json(['status' => 1, 'message' => "Way error"]);

            }
        }else{
            return response()->json(['status' => 1, 'message' => "Way and ID are missing or incorrect"]);

        }
        return $next($request);
    }
}
