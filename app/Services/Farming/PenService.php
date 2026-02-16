<?php

namespace App\Services\Farming;

use App\Models\Pen;
use Illuminate\Http\UploadedFile;

class PenService
{
    public function create(array $data, $farm, $photo = null): Pen
    {
        $pen = new Pen($data);
        $pen->farm_id = $farm->id;

        if ($photo) {
            $pen->photo = $this->handleUpload($photo);
        }

        $pen->save();

        return $pen;
    }

    public function update(Pen $pen, array $data, $photo = null): Pen
    {
        unset($data['photo']);
        $pen->update($data);

        if ($photo) {
            // Delete old photo from Neo bucket
            if ($pen->photo) {
                deleteNeoObject($pen->photo);
            }

            $pen->photo = $this->handleUpload($photo);
            $pen->save();
        }

        return $pen;
    }

    public function delete(Pen $pen): void
    {
        if ($pen->photo) {
            deleteNeoObject($pen->photo);
        }

        $pen->delete();
    }

    protected function handleUpload($file): string
    {
        $fileName = time() . '-' . $file->getClientOriginalName();
        return uploadNeoObject($file, $fileName, 'pens/');
    }
}
