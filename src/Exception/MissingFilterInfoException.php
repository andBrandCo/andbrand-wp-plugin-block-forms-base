<?php

/**
 * File missing data in filter exception
 *
 * @package SebFormsWpPlugin\Exception
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Exception;

use SebFormsWpPluginVendor\EightshiftLibs\Exception\GeneralExceptionInterface;
use InvalidArgumentException;

/**
 * Class MissingFilterInfoException.
 */
final class MissingFilterInfoException extends InvalidArgumentException implements GeneralExceptionInterface
{
	/**
	 * Throw error if there is something wrong with filters.
	 *
	 * @param string $filter Filter name.
	 * @param string $type Filter internal type.
	 * @param string $name Filter internal name.
	 *
	 * @return static
	 */
	public static function viewException($filter, $type, $name): MissingFilterInfoException
	{
		return new MissingFilterInfoException(
			\sprintf(
				/* translators: %1$d is replaced with filter name, %2$d is replaced with filter type, , %3$d is replaced with name. */
				\esc_html__('Filter for %1$s is missing or has a wrong type hint. Provided type: %2$s, provided name: %3$s. Please check your name and try again.', 'seb-forms'),
				$filter,
				$type,
				$name
			)
		);
	}
}
