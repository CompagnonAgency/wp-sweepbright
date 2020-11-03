<?php

/**
 * FieldLegalUpdate.
 *
 * @package FieldLegalUpdate
 */

class FieldLegalUpdate {

	public function __construct() {
	}

	public static function update($estate, $post_id) {
    update_field('legal_mentions', [
      'legal_mentions_en' => $estate['legal']['legal_mentions']['en'],
      'legal_mentions_fr' => $estate['legal']['legal_mentions']['fr'],
      'legal_mentions_nl' => $estate['legal']['legal_mentions']['nl'],
    ], $post_id);
	}

}
