<?php

namespace local_qlapi\data;

global $CFG;
require_once($CFG->libdir . '/externallib.php');

/**
 * Class DataSource
 *
 * @package App
 */
class local_qlapi_datasource
{

    private static $db;


    public static function local_qlapi_get_categories()
    {
        $result = \external_api::call_external_function('core_course_get_categories', null);
        if ($result === false) {
            return null;
        }
        print_object($result);exit;
        return $result;
    }

    public static function searchConferencesByName($name)
    {
        $statement = self::$db->prepare('SELECT * FROM conferences WHERE name LIKE :value');
        $statement->bindValue(':value', '%' . $name . '%');
        $result = $statement->execute();
        if ($result === false) {
            return null;
        }
        return self::resultToArray($result);
    }

    public static function getConferenceById($id)
    {
        $statement = self::$db->prepare('SELECT * FROM conferences WHERE id = :value LIMIT 1');
        $statement->bindValue(':value', $id);
        $result = $statement->execute();
        if ($result === false) {
            return null;
        }
        return (object)$result->fetchArray();
    }

    private static function resultToArray($result)
    {
        $data = [];
        while ($row = $result->fetchArray()) {
            $data[] = (object)$row;
        }
        return $data;
    }

    public static function getSpeakers()
    {
        $result = self::$db->query('SELECT * FROM speakers');
        if ($result === false) {
            return null;
        }
        return self::resultToArray($result);

    }

    public static function bindParamArray($prefix, $values, &$bindArray)
    {
        $str = "";
        foreach($values as $index => $value){
            $str .= ":".$prefix.$index.",";
            $bindArray[$prefix.$index] = $value;
        }
        return rtrim($str,",");
    }

    public static function selectSpeakers($id) {
        $query = 'SELECT
  speakers.id,
  speakers.name,
  speakers.twitter,
  talks.conferenceId as confId
FROM
  speakers
  JOIN talks ON talks.speakerId = speakers.id
  WHERE talks.conferenceId IN (' . implode(',', $id) . ');';
        $statement = self::$db->prepare($query);
        $result = $statement->execute();
        return self::resultToArray($result);
    }

    public static function addSpeaker($name, $twitter) {
        $statement = self::$db->prepare('INSERT INTO "speakers" ("name", "twitter") VALUES (:name, :twitter); ');
        $statement->bindValue(':name', $name);
        $statement->bindValue(':twitter', $twitter);
        $result = $statement->execute();
        return ['id' => self::$db->lastInsertRowID()];
    }


    public static function getSpeakersAtConference($id)
    {
        $statement = self::$db->prepare('SELECT
  speakers.id,
  speakers.name,
  speakers.twitter
FROM
  speakers
  JOIN talks ON talks.speakerId = speakers.id
  WHERE talks.conferenceId = :selectedConference;');
        $statement->bindValue(':selectedConference', $id);
        $result = $statement->execute();
        return self::resultToArray($result);
    }
}
