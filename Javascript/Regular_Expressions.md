
# 정규 표현식
출처: https://beomy.tistory.com/21 [beomy]  
출처: https://droptable.tistory.com/65 [DropTable]

정규식은 문자열에 포함된 문자 조합을 찾기 위해 사용되는 패턴입니다. 코드를 간략하게 만들 수 있으나, 가독성이 떨어질 수 있습니다. 
JavaScript에서 정규표현식은 객체입니다.이러한 패턴은 RegExp의 exec와 test 메소드들, 그리고 String의 match, replace, search, split 메소드들과 함께 사용됩니다.

## 1. 정규식
### 1. 정규식 패턴이 지속될 경우
```javascript
var re = /ab+c/;
```
/ 정규표현식/[Flag];
리터럴 방법을 사용한다.(객체 초기화 방법)

### 2. 정규식 패턴이 변경되는 경우
```javascript
var re = new RegExp("ab+c");
```
new RegExp('정규표현식', ['Flag']);
생성자 함수를 사용하여 동적으로 정규식을 만든다.

## 2. 정규식 패턴 만들기
### 1. 단순 문자열 패턴
직접 찾고자 하는 문자들로 구성된다.
```javascript
/abc/.exec("this is abc"); // ['abc']
```

### 2. 특수문자를 사용한 패턴
하나 이상의 문자를 찾거나, 단순 문자열 패턴보다 다양한 문자열을 찾기 위해 사용한다.
예를 들어, /ab`*`c/ 패턴은 'a' 뒤에 0개 이상의 'b'와 바로 뒤에 'c'가 있는 문자열을 찾습니다. /ab`*`c/는 abbbbbbbbbce에서 abbbbbbbbbc와 매칭됩니다.
```javascript
/ab*c/.exec("this is abbbbbbbbbce"); // ['abbbbbbbbbc']
```

#### 정규식에서의 특수문자
1. \
    1. 단순문자 앞에 \
        /a\d/는 단순 ad와 매칭되지 않습니다. \d는 새로운 의미를 가지게 됩니다. (\d는 0부터 9까지의 숫자와 매칭됩니다.)
    2. 특수문자 앞에 \
        특수문자 앞에 \는 앞에 있는 특수문자를 단순문자로 해석합니다.
        ```javascript
        console.log(/a\*/.exec("aaaaaaa")); // 매칭되지 않음
        console.log(/a\*/.exec("a*aaaaaa")); // a*과 매칭됨
        ```
2. ^  
    입력의 시작 문자열에 매칭된다.
    ```javascript
    console.log(/^A/.exec("an A")); // 매칭되지 않음
    console.log(/^A/.exec("An E")); // 첫번째 'A'와 매칭됨
    ```
3. $
    입력의 끝 문자열에 매칭된다.  
    /x$/ : 문자열이 x로 끝난다 
4. `*`  
    <u>0번</u> 이상 반복되는 문자열에 매칭된다.
    ```javascript
    console.log(/bo*/.exec("A ghost booooed")); // boooo와 매칭됨
    console.log(/bo*/.exec("A bird warbled")); // b와 매칭됨
    console.log(/bo*/.exec("A goat grunted")); // 매칭되지 않음
    ```
    /bo*/ 는 b 1개와 o 0개 이상를 가지고 있는 문자열과 매칭한다.
5. `+`  
    1번 이상 반복되는 문자열에 매칭
    ```javascript
    console.log(/a+/.exec("candy")); // a와 매칭됨
    console.log(/a+/.exec("caaaaaaandy")); // aaaaaaa와 매칭됨
    console.log(/a+/.exec("cndy")); // 매칭되지 않음
    ```
6. ?
    x? x가 존재하거나 존재 하지 않는다.  
    0~1번 반복되는 문자열에 매칭된다.
    ```javascript
    console.log(/e?le?/.exec("angel")); // el에 매칭됨
    console.log(/e?le?/.exec("oslo")); // l에 매칭됨
    ```
    /e?le?/은 'e'가 0~1번 반복 후, 'l'이 온후 'e'가 0~번 반복된 문자열을 찾습니다.  
    *, +, ?, {} 패턴은 가능 한 많은 문자열을 매칭시킵니다.  
    *, +, ?, {} 패턴 뒤에 ? 패턴을 사용하면, 가능한 가장 적은 문자열을 매칭시킵니다.  
    /\d+/는 "123abc"에 "123"과  매칭됩니다.  
    * \d : 0~9 숫자 매칭
    * `+` : 1번이상 반복되는 문자열
    
    하지만, /\d+?/의 경우는 "123abc"에 '1'과만 매칭됩니다.  



출처: https://beomy.tistory.com/21 [beomy]




추가!! 추가!!!!




정규표현식 테스트 링크
http://gskinner.com/RegExr/

