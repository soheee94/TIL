# CSS Naming Rules

## 하이픈으로 구분 된 문자열 사용

```CSS
.redBox{
    /* wrong */
}
.red-box{
    /* right */
}
```

## BEM 명명 규칙

1. selector무엇을 하는지는 이름만 보고 알 수 있습니다.
2. selector를 보기만 해도 어디에 사용할 수 있는지 알 수 있습니다.
3. 클래스 이름 간의 관계를 알기 위해서는 클래스 이름을 살펴 보기만 해도 된다.

```CSS
/* 예시 */
.nav--secondary {
  ...
}
.nav__header {
  ...
}
```

B (Block - Nav, Header, Footer 등) E (Element: \_\_ 두개의 밑줄 ) M (Modifier: -- 두개의 하이픈)

```CSS
/* Block */
.stick-man{

}
/* Element */
.stick-man__head{

}
.stick-man__arms{

}
/* Modifier */
.stick-man--blue{

}
.stick-man--red{

}
.stick-man__head--small{

}
.stick-man__head--big{

}
```

[BEM 더 알아보기](http://getbem.com/naming/)

[참고 사이트](https://www.vobour.com/-css-%EB%94%94%EB%B2%84%EA%B9%85-%EC%8B%9C%EA%B0%84%EC%9D%84-%EC%A0%88%EC%95%BD-%ED%95%A0-%EC%88%98%EC%9E%88%EB%8A%94-css-%EB%AA%85%EB%AA%85-%EA%B7%9C%EC%B9%99)
