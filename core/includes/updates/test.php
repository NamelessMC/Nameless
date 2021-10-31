<?php

return new class extends UpgradeScript {

    public function run(): void {
        $this->setVersion('2.1.0');
    }

};
