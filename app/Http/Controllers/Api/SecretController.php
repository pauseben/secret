<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecretRequest;
use App\Models\Secret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;



class SecretController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSecretRequest $request)
    {

        $secretText = Crypt::encryptString($request->get('secretText'));

        $secret = new Secret([
            'hash' => $request->get('hash'),
            'secretText' => $secretText,
            'expiresAt' => $request->get('expiresAt'),
            'remainingViews' => $request->get('remainingViews')
        ]);

        if($secret->save()){
            return response()->json([
                'status' => true,
                'message' => "Secret Created successfully!"
            ], 200);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function show($hash)
    {  
        $secret = Secret::where('hash','=',$hash)->get();
        foreach($secret as $s){
           
            if($s->expiresAt == 0){ //0 means never expires
                return view('show',compact('secret'));
            }else{
                $now = strtotime(date('Y-m-d H:i:s'));
                $plusMinutes = strtotime($s->created_at.'+'.$s->expiresAt.'minute');
                if($now>=$plusMinutes){
                    return view('error');
                }
            }

            if($s->remainingViews > 0){
                $s->remainingViews -= 1;
                $s->save();
                return view('show',compact('secret'));
            }else{
                return view('error');
            }
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function edit(Secret $secret)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secret $secret)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secret $secret)
    {
        //
    }
}
