# Number generator

[![Build Status](https://travis-ci.org/NoorAdiana/number-generator.svg?branch=master)](https://travis-ci.org/NoorAdiana/number-generator)

Untuk keperluan internal, biasanya ada case dimana dibutuhkan unik number untuk memudahkan dalam pencatatan seperti nomor
nomor transaksi, nomor urut register dan lainnya.

Package ini dibuat untuk menggenerate unik number dengan format YYMMDDNOMOR contohnya (1703010001) secara otomatis saat
create model.

Untuk laravel 5.4 (tested), tapi seharusnya jalan di 5.2 dan 5.3 tapi belum dilakukan testing.

### Install

Menggunakan composer

```
composer require inisiatif/number-generator
```

Tambahkan service provider di ```app.php```

```
\Inisiatif\NumberGenerator\NumberGeneratorServiceProvider::class
```

Jalankan migration

```
php artisam migrate
```

Berikut contoh pemakainnya

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\NumberGenerator\Traits\ModelHasNumberGenerate;

class User extends Model
{
    use ModelHasNumberGenerate;

    protected $fillable = ['name', 'registration_number'];

    protected function getNumberGeneratorAttribute()
    {
        return 'registration_number';
    }
}
```

### Testing

Untuk testing jalankan perintah ini

```
phpunit
```
