# 싱글 쿼터 (') 와 더블 쿼터 (") 의 차이

php에서 문자열 표현 시에 single quote(') 혹은 double quote(") 를 사용한다.
일반적인 경우엔 동일한 결과를 출력한다.

```PHP
<?php
    $foo = 'bar';
    $bar = "bar";

    echo $foo; //bar
    echo $bar; //bar
?>
```

하지만 문자열 내에 다른 문자열 변수를 추가하거나 개행문자 등을 추가할 때는 다르게 출력한다.

```PHP
<?php
    $name = 'apple';
    $foo = 'bar $name \n';
    $bar = "bar $name \n";

    echo $foo; //bar $name \n
    echo $bar; //bar apple
?>
```

PHP 내부적으로 문자열 처리 시 **single quote**는 입력된 내용을 <u>모두 문자열로 처리</u>하는 반면 **double quote**는 <u>파싱이 필요한 데이터가 있는지 확인 후 변환하는 과정을 거처 문자열로 처리</u>하게 되면서 나타나게 됩니다. 즉, single quote는 있는 그대로 문자열로 처리, double quote 변환 작업 후 문자열로 처리 되는 특성을 가지고 있습니다.

처리속도 면에서도 한 단계를 더 거치는 double quote 보다 **single quote가 당연히 더 빠릅니다**. 하지만 개발자나 이용자가 느낄 정도로 크게 차이가 나지는 않으므로 일반적인 경우 성능상의 차이는 거의 없다고 볼 수 있습니다. 
