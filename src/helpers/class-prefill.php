<?php
/**
 * Helpers for prefill
 *
 * @package Eightshift_Forms\Helpers
 */

declare( strict_types=1 );

namespace Eightshift_Forms\Helpers;

use Eightshift_Forms\Core\Filters;

/**
 * Helpers for prefill
 */
class Prefill {

  const FAILED_TO_PREFILL_LABEL = 'Unable to prefill options';

  /**
   * Returns the data of project-defined prefill source.
   *
   * Needs to return array with the following 2 keys:
   * - label
   * - value
   *
   * @param  string $prefill_source_name Name of the prefill source as defined in project.
   * @param  string $filter_name         Name of the filter we're getting data for.
   * @return array
   */
  public static function get_prefill_source_data( string $prefill_source_name, string $filter_name ): array {
    if ( ! has_filter( Filters::PREFILL_GENERIC_MULTI ) ) {
      return [
        [
          'label' => esc_html__( 'Unable to prefill options, selected options not defined', 'eightshift-forms' ),
          'value' => 'no-value',
        ],
      ];
    }

    $prefill_data = apply_filters( $filter_name, [] );

    if ( ! isset( $prefill_data[ $prefill_source_name ], $prefill_data[ $prefill_source_name ]['data'] ) ) {
      return [
        [
          'label' => esc_html__( 'Unable to prefill options, no data defined', 'eightshift-forms' ),
          'value' => 'no-value',
        ],
      ];
    }

    return $prefill_data[ $prefill_source_name ]['data'];
  }
}