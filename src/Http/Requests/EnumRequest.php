<?php

namespace Jiannei\Enum\Laravel\Http\Requests;

use Closure;
use Illuminate\Http\Request;

/**
 * @internal This class is only used to get mixed into \Illuminate\Http\Request
 *
 * @mixin Request
 */
final class EnumRequest
{
    public function transformEnums(): Closure
    {
        return function (array $transformations, bool $strict): void {
            foreach ($transformations as $key => $enumClass) {
                if (! $this->offsetExists($key)) {
                    continue;
                }

                $requestKey = $this->offsetGet($key);
                $enumKeyOrValue = (is_numeric($requestKey) && ! $strict) ? (int) $requestKey : $requestKey;

                $transformedKey = $enumClass::make($enumKeyOrValue, $strict);

                $this->offsetSet($key, $transformedKey);
            }
        };
    }
}
