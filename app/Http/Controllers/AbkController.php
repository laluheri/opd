<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Abk;
use App\Struktural;
use App\Fungsional;
use App\User;
use Excel;

class AbkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function jabatanStruktural()
    {
        $datas = Struktural::get()->where('kode_opd','>=','02');
        return view ('abk.struktural', compact('datas'));
    }
    public function jabatanFungsional()
    {
        $datas = Fungsional::get()->where('kode_opd','>=','02');
        return view ('abk.fungsional', compact('datas','$id'));
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

    public function importAbkFungsional(Request $request)
    {
        $i = 0;
        $id = 1;
        
        
        $kode_opd = $request->get('jabatan');
        $this->validate($request, [
            'importAbk' => 'required'
        ]);

        if ($request->hasFile('importAbk')) {
            $path = $request->file('importAbk')->getRealPath();

            $data = Excel::load($path, function($reader){})->get();
            $a = collect($data);

            if (!empty($a) && $a->count()) {
                foreach ($a as $key => $value) {
                    //dd($value->sub);
                    $sub_id = $value->sub;
                    if($value->sub != "0.0"){
                        
                        $id = Abk::max('id_abk');
                        $sub_id = $id-$i;
                        $i++;  
                    }else{
                        $i=0;
                    }
                     
                    
                    $insert[] = [
                           
                            'jabatan_fungsional_id' => $request->get('jabatan'),
                            'uraian_tugas' => $value->uraian_tugas, 
                            'satuan_hasil' => $value->satuan_hasil, 
                            'waktu_penyelesaian' => $value->waktu_penyelesaian,
                            'waktu_kerja_efektif' => $value->waktu_kerja_efektif,
                            'beban_kerja' => $value->beban_kerja,
                            'pegawai_dibutuhkan' => $value->pegawai_dibutuhkan,
                            'keterangan' => "$id . $i",
                            'sub' => "$sub_id",
                            'username' => Auth::user()->name,
                            
                            
                        ];

                    abk::create($insert[$key]);
                        
                    }
                  
            };
        }
        alert()->success('Berhasil.','Data telah diimport!');
        return back();
    }

    public function abkFungsionalView(){
       $datas = abk::orderBy('id_abk', 'ASC')->get();
        
       return view ('abk.fungsionalView', compact('datas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}