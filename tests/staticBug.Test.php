<?php
require_once __DIR__.'/cfg.php';

use Tester\Assert as Is;

// wont work as expected
abstract class Ancestor {
    static $prop; // cannon be declared abstract :(
}

class Descent1 extends Ancestor {
}

class Descent2 extends Ancestor {
}

Ancestor::$prop = 'parent';
Descent1::$prop = 'child1';
Descent2::$prop = 'child2';

Is::same('child2', Ancestor::$prop);
Is::same('child2', Descent1::$prop);
Is::same('child2', Descent2::$prop);
// because same as all descents/parents property

// rededine solution
abstract class AncestorFix1 {
    static $prop; // cannon be declared abstract :(
}

class Descent1Fix1 extends AncestorFix1 {
    static $prop; // redefined are unbound to parent
}

class Descent2Fix1 extends AncestorFix1 {
    // if you forget this, same problem
}

AncestorFix1::$prop = 'parent';
Descent1Fix1::$prop = 'child1';
Descent2Fix1::$prop = 'child2';

Is::same('child2', AncestorFix1::$prop); // same as Descent2::$prop
Is::same('child1', Descent1Fix1::$prop); // was unbound
Is::same('child2', Descent2Fix1::$prop);


// trait solution
trait AncestorFix2Trait {
    static $prop = 'parent';
}

class Descent1Fix2 {
    use AncestorFix2Trait;
}

class Descent2Fix2 {
    use AncestorFix2Trait;
}

AncestorFix2Trait::$prop = 'parent';
Descent1Fix2::$prop = 'child1';
Descent2Fix2::$prop = 'child2';

Is::same('parent', AncestorFix2Trait::$prop);
Is::same('child1', Descent1Fix2::$prop);
Is::same('child2', Descent2Fix2::$prop);
