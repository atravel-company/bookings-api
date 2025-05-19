<?php
namespace App\Http\Requests\Traits;

use Illuminate\Support\Str;

trait NormalizesCommaSeparated
{
  protected function prepareForValidation()
  {
    $all = $this->all();

    foreach ($all as $key => $value) {
      // only strings containing commas
      if (is_string($value) && Str::contains($value, ',')) {
        $items = explode(',', $value);

        // cast numeric strings to ints
        $items = array_map(function ($item) {
          return is_numeric($item)
            ? (strpos($item, '.') === false ? (int) $item : (float) $item)
            : $item;
        }, $items);

        $this->merge([$key => $items]);
      }
    }
  }
}
