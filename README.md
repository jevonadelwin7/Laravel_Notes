# Laravel Notes

![Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

Catatan penulisan kode-kode Laravel. Support versi laravel 8 > 

# Laravel Bootstrap Auth Scaffolding 
```sh
composer require laravel/ui
```

```sh
php artisan ui bootstrap --auth
```

```sh
npm install && npm run dev
```

```sh
php artisan migrate
```

```sh
php artisan serve
```

> Note: Untuk build Laravel UI Auth dapat membuat perintah `npm run build` 

# Update Gambar dengan menghapus gambar lama.

### View
```php
<div class="col-md-6 col-lg-4">
    @foreach ($banner as $item )                               
     @error('image')
     <div class="alert alert-danger mt-2">
        {{ $message }}
     </div>
     @enderror
    <form action="{{url('updateBanner',$item->id)}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
        <img src="{{ asset('frontend/img/'.$item->image_name) }}" class="img-fluid rounded" alt="banner1">
        <div class="form-group">
            <input type="text" name="nama" class="form-control" id="title1" value="{{$item->image_name}}" disabled></div>
        <div class="form-group">
            <label for="title1">Judul Banner 1</label>
            <input type="text" name='title'class="form-control" id="title1" value="{{$item->image_title}}">
        </div>
        <div class="form-group">
            <label for="title2">Sub Judul 2 </label>
            <input type="text" name='subtitle' class="form-control" id="title2" value="{{$item->image_subtitle}}">
        </div>
        <div class="form-group">
            <label for="exampleFormControlFile1">Upload File Gambar</label>
            <input type="file" class="form-control-file" id="gambar1" name="image" accept="image/*,.jpg">
            <small id="titleHelp1" class="form-text text-muted">Format .JPG 1920x1080</small>
        </div>
        <div class="card-action">
            <button type="submit" class="btn btn-success">Simpan</button>                             
        </div>
    </form>
@endforeach
</div>
```

### Controller

```php
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
```

