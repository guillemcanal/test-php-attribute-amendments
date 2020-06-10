<?php 

declare(strict_types=1);
ini_set('assert.exceptions', '1');

// Declare some PHP Attributes

<<Attribute(Attribute::TARGET_CLASS)>>
class AttributeOnClass {
  public function __construct(
    public string $value,
  ) {}
}

<<Attribute(Attribute::TARGET_CLASS)>>
class AttributeOnClass2 {
  public function __construct(
    public bool $boolean,
    public int $integer,
  ) {}
}

<<Attribute(Attribute::TARGET_METHOD)>>
class AttributeOnMethod extends AttributeOnClass {}

// Helper function

function getAttributes(ReflectionClass|ReflectionMethod $r) {
  return array_map(
    fn (ReflectionAttribute $attr) => $attr->newInstance(),
    $r->getAttributes()
  );
}

// Validate Attribute Repeatability

<<AttributeOnClass("foo")>>
<<AttributeOnClass("bar")>>
class ClassWithRepeatedAttributes {}

try {
  getAttributes(new ReflectionClass(ClassWithRepeatedAttributes::class));
} catch (\Throwable $e) {
  assert("Attribute 'AttributeOnClass' must not be repeated" === $e->getMessage());
}

// Group statement for Attributes

<<
  AttributeOnClass("foo"),
  AttributeOnClass2(true, 101),
>>
class ClassWithAttributes {}

$attrs = getAttributes(new ReflectionClass(ClassWithAttributes::class));
assert(count($attrs) === 2);
assert($attrs[0] instanceof AttributeOnClass);
assert($attrs[0]->value === "foo");
assert($attrs[1] instanceof AttributeOnClass2);
assert($attrs[1]->boolean === true);
assert($attrs[1]->integer === 101);

// Extract attributes from a given method

class ClassWithMethodAttribute {
  <<AttributeOnMethod("bar")>>
  public function foo(): void {}
}

$attrs = getAttributes(new ReflectionMethod(ClassWithMethodAttribute::class, 'foo'));
assert(count($attrs) === 1);
assert(current($attrs) instanceof AttributeOnMethod);
assert(current($attrs)->value === "bar");

// Validate Attribute Target Declarations

<<
  AttributeOnClass("foo"),
  AttributeOnMethod("bar"),
>>
class ClassMisusingMethodAttributes {}

try {
  getAttributes(new ReflectionClass(ClassMisusingMethodAttributes::class));  
} catch (\Throwable $e) {
  assert("Attribute 'AttributeOnMethod' is not allowed here" === $e->getMessage());
}
