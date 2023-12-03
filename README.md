Qed by Whateverthing
====================

Qed (pronounced "ked") provides method chaining on BCMath operations, allowing
you to write smoother and more readable calculation code.

Qed also includes polyfill functions for `bcround`, `bcceil`, and `bcfloor`,
which have official implementations coming in
[a future PHP release](https://wiki.php.net/rfc/adding_bcround_bcfloor_bcceil_to_bcmath).

(Note that Qed's polyfills are not guaranteed to be mathematically sound. Please
don't use them for anything even the slightest bit important.)

## Example

**A set of basic operations:**

```php
$num = new Qed('123');
$num = $num->add('27')->mul('4');

echo $num->value; // 600
```

**Setting the scale:**

BCMath operations have a concept of a "scale factor". This can become quite
important - certain numbers may end up being unintentionally truncated if the
scale is not set to a suitable value.

Scale may be a bit different than precision. For more information, see the [bcscale() documentation](https://www.php.net/manual/en/function.bcscale.php) on the PHP website.

```php
$num = new Qed('123', 5);
$divisor = new Qed('456');
$num = $num->div($divisor)->mul('10');

echo $num->value; // 2.69730
```

Note that the scale follows the left-side of the flow. If a `Qed` object is
passed as the divisor (or any other operand), that object's scale will be
ignored.

**Rounding:**

While `bcround()` isn't yet available in PHP, Qed provides a polyfill function
and a helper method. This polyfill function is primitive and deeply unreliable.

But, if you want to risk it, here's how to use it:

```php
$num = new Qed('123', 10);
$divisor = new Qed('456');
$num = $num->div($divisor)->mul('10')->round(2);

echo $num->value; // 2.70
```

Note that the precision value is NOT the same as a normal "scale" value, because
scale factor cannot be a negative value (but precision can). The returned Qed
object will have the same scale value as the originating instance, the precision
value is only applied to the internals of `bcround`.

There is a second parameter, `$roundHalf`, which can be one of the
`PHP_ROUND_HALF_*` constants. See [php.net/round](https://www.php.net/round)
for more information about how they are expected to work.
