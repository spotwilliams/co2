```php

<?php

namespace App\Infrastructure\Doctrine\Repositories;

use src\Entities\Dummy;

class DoctrineVotingRepository extends DoctrineReadRepository implements DummyRepository
{
    public function getEntity()
    {
        return Voting::class;
    }
}
