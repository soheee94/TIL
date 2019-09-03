## Level 1

### 문제
문자열 s에 나타나는 문자를 큰것부터 작은 순으로 정렬해 새로운 문자열을 리턴하는 함수, solution을 완성해주세요.
s는 영문 대소문자로만 구성되어 있으며, 대문자는 소문자보다 작은 것으로 간주합니다.

**입출력 예**  
s : Zbcdefg  
return : gfedcbZ

### 답안
```javascript
function solution(s) {
    var answer = s.split('').sort().reverse().join('');
    return answer;
}
```

### 추가
`join()` 메서드는 배열의 모든 요소를 연결해 하나의 문자열로 만든다.