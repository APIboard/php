<?php

namespace Apiboard\Checks\Concerns;

trait NormalisesArrays
{
    public function normaliseArray(array $data, string ...$keysToRemove): array
    {
        ksort($array);

        foreach ($data as $key => $value) {
            foreach ($keysToRemove as $keyToRemove) {
                if (fnmatch($keyToRemove, $key)) {
                    unset($data[$key]);

                    continue;
                }
            }

            if (is_array($value)) {
                $data[$key] = $this->normaliseArray($value, ...$keysToRemove);
            }
        }

        return $data;
    }
}
