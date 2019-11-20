[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 22. 커스텀 Hooks 만들기

컴포넌트를 만들다보면, <u>반복되는 로직</u>이 자주 발생합니다. 예를 들어서 input 을 관리하는 코드는 관리 할 때마다 꽤나 비슷한 코드가 반복되죠.

이번에는 그러한 상황에 `커스텀 Hooks` 를 만들어서 반복되는 로직을 쉽게 재사용하는 방법을 알아보겠습니다.

커스텀 Hooks 를 만들 때에는 보통 이렇게 `use` 라는 키워드로 시작하는 파일을 만들고 그 안에 함수를 작성합니다.

커스텀 Hooks 를 만드는 방법은 굉장히 간단합니다. 그냥, 그 안에서 `useState`, `useEffect`, `useReducer`, `useCallback` 등 `Hooks` 를 사용하여 원하는 기능을 구현해주고, 컴포넌트에서 사용하고 싶은 값들을 반환해주면 됩니다.


```javascript
// useInputs.js

import { useState, useCallback } from 'react';

function useInputs(initialForm){
    const [ form, setForm ] = useState(initialForm);
    const onChange = useCallback(e =>{
        const { name, value } = e.target;
        setForm(form => ({...form, [name] : value}))
    }, []);
    const reset = useCallback(() => setForm(initialForm),[]);
    return [form, onChange, reset];
}

export default useInputs;

```

