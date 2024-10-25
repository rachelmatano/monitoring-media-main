<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaProve;
use App\Models\ProveGallery;
use Auth, Session, DB, Validator, File;

class ProveGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $page = "admin/pages/prove_gallery/";
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $proveId)
    {
        $pageName = "Adding Picture Or Video For Media Contribution";
        $prove = MediaProve::where('id',$proveId)->first();
        return view($this->page.'create',compact('pageName','prove'));
    }

    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'prove_id'=>'required','file'=>'required','tipe'=>'required'
        ]);
        return $validate;
    }
    public function moveTheFile(Request $req){
        if($req->hasFile('file')){
            $fileName = time().'_prove_'.$req->prove_id.'.'.$req->file->extension();
            $req->file->move('prove_galleries',$fileName);
            return $fileName;
        }
        return '-';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                $fileName = $this->moveTheFile($request);
                $data = ProveGallery::create([
                    'prove_id'=>$request->prove_id,
                    'link_path'=>$fileName,
                    'tipe'=>$request->tipe
                ]);
                if($data){
                    return redirect('media_prove/'.$request->prove_id)->with('success','Data successfully Added!');
                }
                return redirect('prove_gallery_add/'.$request->prove_id)->with('error','Data Failed to be Added!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('prove_gallery_add/'.$request->prove_id)->with('error','Data Failed to be added! (Database Error  : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('prove_gallery_add/'.$request->prove_id)->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $prove = ProveGallery::where('id',$id)->first();
            if($prove->link_path!='-'){
                File::delete('prove_galleries/'.$prove->link_path);
            }
            $deleted = ProveGallery::where('id',$id)->delete();
            DB::commit();
            return redirect('media_prove/'.$prove->prove_id)->with('success','Data successfully deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('media_prove/'.$prove->prove_id)->with('error','Data failed to be deleted!');
        }
    }
}
