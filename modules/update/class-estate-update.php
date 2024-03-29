<?php

/**
 * FieldEstateUpdate.
 *
 * @package FieldEstateUpdate
 */

class FieldEstateUpdate
{

  public function __construct()
  {
  }

  public static function update($estate, $post_id)
  {
    update_field('estate', [
      'estate_id' => $estate['id'],
    ], $post_id);

    update_field('estate', [
      'estate_status' => $estate['status'],
    ], $post_id);

    update_field('estate', [
      'estate_title' => [
        'estate_title_en' => $estate['description_title']['en'],
        'estate_title_fr' => $estate['description_title']['fr'],
        'estate_title_nl' => $estate['description_title']['nl'],
      ],
    ], $post_id);

    update_field('estate', [
      'estate_description' => [
        'estate_description_en' => $estate['description']['en'],
        'estate_description_fr' => $estate['description']['fr'],
        'estate_description_nl' => $estate['description']['nl'],
      ],
    ], $post_id);

    if ($estate['is_project']) {
      update_field('estate', [
        'is_project' => $estate['is_project'],
      ], $post_id);
    } else {
      update_field('estate', [
        'is_project' => 0,
      ], $post_id);
    }

    if ($estate['project_id']) {
      update_field('estate', [
        'project_id' => $estate['project_id'],
      ], $post_id);
    } else {
      update_field('estate', [
        'project_id' => 0,
      ], $post_id);
    }

    if (isset($estate['properties']) && is_countable($estate['properties'])) {
      if (count($estate['properties']) > 0) {
        $properties = [];
        foreach ($estate['properties'] as $property) {
          $properties[] = [
            'acf_fc_layout' => 'property_layout',
            'property_item' => $property['id'],
          ];
        }
        update_field('estate', [
          'properties' => $properties,
        ], $post_id);
      }
    }
  }
}
