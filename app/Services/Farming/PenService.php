<?php

namespace App\Services\Farming;

use App\Models\Pen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PenService
{
    public function create(array $data, $farm, ?UploadedFile $photo = null): Pen
    {
        $pen = new Pen($data);
        $pen->farm_id = $farm->id;

        if ($photo) {
            $pen->photo = $this->handleUpload($photo);
        }

        $pen->save();

        return $pen;
    }

    public function update(Pen $pen, array $data, ?UploadedFile $photo = null): Pen
    {
        unset($data['photo']);
        $pen->update($data);

        if ($photo) {
            if ($pen->photo && Storage::disk('public')->exists(str_replace('storage/', '', $pen->photo))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $pen->photo));
            }

            $pen->photo = $this->handleUpload($photo);
            $pen->save();
        }

        return $pen;
    }

    public function delete(Pen $pen): void
    {
        if ($pen->photo && Storage::disk('public')->exists(str_replace('storage/', '', $pen->photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $pen->photo));
        }

        $pen->delete();
    }

    protected function handleUpload(UploadedFile $file): string
    {
        $fileName = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('pens', $fileName, 'public');
        return 'storage/pens/' . $fileName;
    }
}
