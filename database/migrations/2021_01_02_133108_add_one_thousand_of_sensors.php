<?php

use Illuminate\Database\Migrations\Migration;

class AddOneThousandOfSensors extends Migration
{
    public function up()
    {
        $i = 0;

        /** @var \Co2Control\Repositories\PersistRepository $persist */
        $persist = \Illuminate\Foundation\Application::getInstance()->get(\Co2Control\Repositories\PersistRepository::class);
        while (++$i <= 1000) {
            $persist->save(new \Co2Control\Entities\Sensor());
        }
    }

    public function down()
    {
    }
}
