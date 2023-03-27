<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Transformer utility for retrieving record data.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use Exception;

/**
 * Transformer utility for retrieving record data.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the course.
 * @param int $recordid The id of the record.
 * @param int $databaseid The id of the database.
 * @param int $cmid The course module id.
 * @return array
 */

function record(array $config, string $lang, int $recordid, int $databaseid, int $cmid): array {

    try {
        $repo = $config['repo'];
        $coursemodule = $repo->read_record_by_id('course_modules', $cmid);
        $status = $coursemodule->deletioninprogress;
        if ($status == 0) {
            $description = 'the record of the database';
        } else {
            $description = 'deletion in progress';
        }
    } catch (Exception $e) {
        // OBJECT_NOT_FOUND.
        $description = 'deleted';
    }

    $url = $config['app_url'] . '/mod/data/view.php?d='. $recordid . '&rid=' . $databaseid;

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/article',
            'name' => [
                $lang => 'Database record',
            ],
            'description' => [
                $lang => $description,
            ],
        ],
    ];
}
