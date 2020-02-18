# Early Return

“결과를 할당하는 것”은 “이게 최종 값이며, 처리는 여기서 멈춘다”라는 의도를 설명하지 않으며, “이 결과는 완료된거야? 수정할 수 있는거야?”라는 질문을 남기고, 결과를 수정하는 실수를 허용하기도 한다. 그러므로 함수가 더 이상 의미있는 동작을 하지 않는다는 것을 알자마자 반환하고, if/else 대신에 if/return 구조를 사용하여 들여쓰기를 최소한으로 줄이자.

아래의 코드는 자연스럽게 읽기 어려운 코드이다.

```javascript
function getValue(id) {
	if (id > 0) {
		if (id > 10) {
			value = "Id is greater than 10.";
		} else {
			value = "Id is greater than 0 and below 10."
		}
	} else if (id == 0) {
		value = "This id is zero.";
	} else {
		value = "Invalid id.";
	}
	return value;
}
```
Early Return 적용 후의 코드이다.

Early Return 형태로 작성한 코드가 더 자연스럽게 읽혀지고 의미도 명확하다. if 조건문에 들어오면 해당하는 동작을 하고 return 한다. 이 함수에서 더 이상의 동작은 없다. 이 함수가 어떤 조건일 때 무엇을 반환하는지 매우 명확하다. 또한 아래에 누군가가 실수로 결과를 수정하려는 코드를 넣는다고 해도 안전하다.

```javascript
function getValue(id) {

	if (id == 0) {
		return "This id is zero."
	}

	if (id < 0) {
		return "Invalid id.";
	}

	if (id > 10) {
		return "Id is greater than 10.";
	}

	return "Id is greater than 0 and below 10.";
}
```


물론 Early Return 하는 코드를 작성할 때에는 유의할 것이 있다. 조건문의 범위 및 순서를 잘 고려하면서 배치해야한다. 만약 아래와 같이 코드를 수정했다면 원래 실행에 문제 없었던 동작에도 문제가 생겨버리는 것이다.


[ 참고 ]
https://torquemag.io/2018/04/code-review-part-1-fixing-design-flaw-return-early-strategy/
https://jheloper.github.io/2019/06/write-early-return-code/