<?php

/**
 * @author Nils Oesting <noesting@uos.de>
 */
class FixDatafieldsSemClass extends Migration
{

    public function description()
    {
        return 'fix sem_class for mooc datafields (see https://github.com/virtUOS/mooc.ip/issues/385)';
    }

    public function up()
    {
        $mooc_sem_class = $this->getMoocSemClassID();

        $db = DBManager::get();

        $stm = $db->prepare(
            "UPDATE `datafields`
             SET `object_class` = ?
             WHERE name = '(M)OOC Startdatum'
            "
        );
        $stm->execute(array($mooc_sem_class));

        $stm = $db->prepare(
            "UPDATE `datafields`
             SET `object_class` = ?
             WHERE name = '(M)OOC Dauer'
            "
        );
        $stm->execute(array($mooc_sem_class));

        $stm = $db->prepare(
            "UPDATE `datafields`
             SET `object_class` = ?
             WHERE name = '(M)OOC Hinweise'
            "
        );
        $stm->execute(array($mooc_sem_class));

        $stm = $db->prepare(
            "UPDATE `datafields`
             SET `object_class` = ?
             WHERE name = '(M)OOC-Preview-Image'
            "
        );
        $stm->execute(array($mooc_sem_class));

        $stm = $db->prepare(
            "UPDATE `datafields`
             SET `object_class` = ?
             WHERE name = '(M)OOC-Preview-Video (mp4)'
            "
        );
        $stm->execute(array($mooc_sem_class));

    }

    public function down()
    {
        $mooc_sem_class_id = $this->getMoocSemClassID();
        Mooc::onDisable($mooc_sem_class_id);
    }
    
    private function getMoocSemClassID()
    {
        return Config::get()->getValue(\Mooc\SEM_CLASS_CONFIG_ID);
    }
}
