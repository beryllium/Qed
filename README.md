Qed by Whateverthing
====================

Qed (pronounced "ked") is a wrapper for BCMath functionality.

It uses method chaining to allow operations to flow from previous operations.

## Example

**A set of basic operations:**

```php
$num = new Qed('123');
$num = $num->add('27')->mul('4');

echo $num->value; // 600
```

**Setting the scale:**

BCMath operations have a concept of a "scale factor". This can become quite important - certain numbers may end up being unintentionally truncated if the scale is not set to a suitable value.

Scale may be a bit different than precision. For more information, see the [bcscale() documentation](https://www.php.net/manual/en/function.bcscale.php) on the PHP website.

```php
$num = new Qed('123', 10);
$divisor = new Qed('456');
$num = $num->div($divisor)->mul('10');

echo $num->value; // 2.6973
```

Note that the scale follows the left-side of the flow. If a `Qed` object is passed as the divisor (or any other operand), that object's scale will be ignored.