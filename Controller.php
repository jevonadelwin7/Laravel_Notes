    public function updateBanner(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'dimensions:max_width=1920,max_height=1080'
        ],
        [
            'image.dimensions'=> 'Dimesi gambar maksimal 1920x1080'//message erro
        ]);
        $ubah = Banner::findorfail($id);
        $oldPhoto = $ubah->image_name;
        if($request->file('image') == "") {

            $ubah->update([
                'image_title'=>$request['title'],
                'image_subtitle'=>$request['subtitle'],
            ]);
        //return redirect('/admin/banner');
        } else {
        $newBanner = $request->file('image');
        $filename = time() . '.' . $newBanner->getClientOriginalExtension();
        $newBanner->move(public_path('/frontend/img/'), $filename);

        // Hapus file lama jika ada
        if ($oldPhoto) {
            $oldPhotoPath = public_path('/frontend/img/' . $oldPhoto);
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        }
        $dt =[
            'image_name'=>$filename,
            'image_title'=>$request['title'],
            'image_subtitle'=>$request['subtitle'],

        ];
        $ubah->update($dt);
        
       // return redirect('/admin/banner');
        }
        if($ubah){
            //redirect dengan pesan sukses
            return redirect('/admin/banner')->with('success');
        }else{
            //redirect dengan pesan error
            return redirect('/admin/banner')->with('error');
        }
    }
