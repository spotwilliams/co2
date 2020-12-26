```php

<?php

namespace App\Infrastructure\Doctrine\Embeddables;

use Digbang\Utils\Doctrine\Mappings\Embeddables\EnumMapping;
use src\Enumerables\Dummy;

class DummyMapping extends EnumMapping
{
    public function mapFor(): string
    {
        return Dummy::class;
    }
}

```
