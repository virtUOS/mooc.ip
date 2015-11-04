<?php
/**
 * 022_convert_overview_field.php
 *
 * @author Till Glöggler <tgloeggl@uos.de>
 */

require __DIR__.'/../vendor/autoload.php';

class ConvertOverviewField extends Migration
{

    public function description()
    {
        return 'convert html-block overview field contents to config';
    }

    public function up()
    {
        $db = DBManager::get();

        try {
            $block_id = $db->query("SELECT id FROM mooc_blocks
                WHERE type = 'HtmlBlock' AND seminar_id IS NULL")->fetchColumn();

            $stmt = $db->prepare("SELECT json_data FROM mooc_fields
                WHERE block_id = ?");

            $stmt->execute(array($block_id));
            $content = utf8_decode(json_decode($stmt->fetchColumn()));
        } catch (PDOException $e) {}

        Config::get()->create(Mooc\OVERVIEW_CONTENT, array(
            'value'       => $content,
            'is_default'  => 0,
            'type'        => 'string',
            'range'       => 'global',
            'section'     => 'global',
            'description' => 'Inhalt der Übersichtsseite für Mooc.IP'
        ));

        // TODO: überflüssiges Feld löschen
    }

    function down()
    {
        Config::get()->delete(Mooc\OVERVIEW_CONTENT);

        // TODO: Feld wieder hinzufügen
    }
}


