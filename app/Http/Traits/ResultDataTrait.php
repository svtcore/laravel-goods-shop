<?php

namespace App\Http\Traits;

trait ResultDataTrait {

    public function check_result($result): bool
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

}

?>
