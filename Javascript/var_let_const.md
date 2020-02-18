# var, let, const

##  변수 값의 변환
`var`를 사용하면 변수 선언의 경우 할당되는 값이 유동적으로 변경될 수 있는 단점
```javascript
var name = "HELLO";
console.log(name);

var name = "WORLD";
console.log(name);

// output: Marcus
// output: Jogeonsang
```

ES6 업데이트 이후로 추가된 변수 선언 방식인 `let`과 `const`는 `var`와 같은 선언 방식을 막고있다.
```javascript
let name = "HELLO";
console.log(name); 

let name = "WORLD";
console.log(name);
// output: Identifier 'name' has already been declared
```
위와 같이 `let`을 사용했을 경우에는 name이 이미 선언되었다는 에러 메시지가 나오는걸 볼 수 있다.
위에 코드에는 `let`만 케이스로 집어넣었지만 `const`도 마찬가지로 변수 재할당이 안된다는 특징을 가지고있다.


`let`과 `const`의 차이점은 변수의 `immutable`여부이다.

`let`은 변수에 재할당이 가능하지만,
`const`는 변수 재선언, 재할당 모두 불가능하다.

## 변수의 유효범위
`var`는 function scope 를 가지게 되고, `let`, `const` 는 block scope를 가지고 있다.

```javascript
for(var i=0; i<10; i++){
    setTimeout(function(){console.log(i)}, i*1000);
}

// output : 10 이 10번 찍힌다
```

따라서 for 문에서 `var i = 0` 으로 선언된 i는 전역변수로 선언되고
`setTimeout()` 내부에서 사용되는 i 역시 전역 변수를 참조한다.

즉, for 구문을 통해 10번의 반복이 끝난 뒤 setTimeout()이 첫 번째 파라미터로 지정된 callback 함수를 실행하게 되는데 이때는 이미 i의 값이 10으로 증가된 상태이므로 10번 모두 10이 출력되는 것이다.

(javascript는 먼저 할 일(=> for문 10번)을 마친 뒤 queue에 쌓아 두었던 setTimeout을 실행한다. 그래서 전역변수인 i는 이미 10이 되버린 상태에서 setTimeout을 10번 실행하기에 10이 10번 찍히게 되는 것이다.)

해결 방법
1. 즉시 실행 함수
```javascript
for (var i = 0; i < 10; i++) {
    (function(index) {
        setTimeout(function() {
            console.log(index);
        }, 3000);
    })(i);
}
```

2. ES6 let 사용
```javascript
for(let i=0; i<10; i++){
    setTimeout(function(){console.log(i)}, i*1000);
}

// output : 0 1 2 3 4 5 6 7 8 9
```

`let`은 block scope 이기 때문에 block 마다 i를 가지고 있다. 따라서 각각 다른 i가 가지고 있는 0 1 2 3 4 5 6 7 8 9 이 출력된다. 

[ 참고 ]
https://velog.io/@marcus/2019-02-10-1702-%EC%9E%91%EC%84%B1%EB%90%A8
https://steemit.com/js/@huna/js-settimeout