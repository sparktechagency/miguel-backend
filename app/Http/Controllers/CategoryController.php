<?php

namespace App\Http\Controllers;

use App\Http\Requests\BPMRequest;
use App\Http\Requests\GenreRequest;
use App\Http\Requests\KeyRequest;
use App\Http\Requests\LicenseReqeust;
use App\Http\Requests\TypeRequest;
use App\Models\BPM;
use App\Models\Genre;
use App\Models\Key;
use App\Models\License;
use App\Models\Type;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function genre() {
        try{
            $genre = Genre::orderBy('id','desc')->get();
            return $this->sendResponse($genre,"Genre retrived successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createGenre(GenreRequest $genreRequest) {
        try{
            $genre = Genre::create($genreRequest->validated());
            return $this->sendResponse($genre,"Genre create successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function deleteGenre($genreId) {
        try{
            $genre = Genre::findOrFail($genreId);
            $genre->delete();
            return $this->sendResponse([],"Genre delete successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }

    public function bpm() {
        try{
            $bpm = BPM::orderBy('id','desc')->get();
            return $this->sendResponse($bpm,"BPM retrived successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createBpm(BPMRequest $BPMRequest)
    {
        try {
            $validated = $BPMRequest->validated();
            $bpm = BPM::first();
            if ($bpm) {
                $bpm->value = $validated['value'];
                $bpm->save();
            } else {
                BPM::create($validated);
            }

            return $this->sendResponse([],"BPM created or updated successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

    public function deleteBpm($bpmId) {
        try{
           $bpm = BPM::findOrFail($bpmId);
           $bpm->delete();
           return $this->sendResponse([],"BPM delete successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function key() {
        try{
            $key = Key::orderBy('id','desc')->get();
            return $this->sendResponse($key,"Key retrived successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createKey(KeyRequest $keyRequest) {
        try{
            $key = Key::create($keyRequest->validated());
            return $this->sendResponse($key,"Key create successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function deleteKey($keyId) {
        try{
            $key = Key::findOrFail($keyId);
            $key->delete();
            return $this->sendResponse([],"Key delete successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }

    public function license() {
        try{
            $license = License::orderBy('id','desc')->get();
            return $this->sendResponse($license,"License retrived successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createLicense(LicenseReqeust $licenseReqeust) {
        try{
            $license = License::create($licenseReqeust->validated());
            return $this->sendResponse($license,"License create successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function deleteLicense($licenseId) {
        try{
            $license = License::findOrFail($licenseId);
            $license->delete();
            return $this->sendResponse([],"License delete successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }

    public function type() {
        try{
            $type = Type::orderBy('id','desc')->get();
            return $this->sendResponse($type,"Type retrived successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function createType(TypeRequest $typeRequest) {
        try{
            $type = Type::create($typeRequest->validated());
            return $this->sendResponse($type,"Type create successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }
    public function deleteType($typeId) {
        try{
            $type = Type::findOrFail($typeId);
            $type->delete();
            return $this->sendResponse([],"Type delete successfully.");
        }catch(Exception $e){
            $this->sendError("An error occurred: ".$e->getMessage(),[],500);
        }
    }

}
