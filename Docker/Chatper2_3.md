# 02. 도커 엔진
## 2.3 도커 이미지
### 2.3.1 도커 이미지 생성
1. 기존의 이미지로부터 변경사항을 가진 컨테이너 만들기
    ```
   docker run -i -t --name commit_test ubuntu:14.04
   echo test_first! >> fisrt
   ```
2. commit_test 컨테이너를 commit_test:first 이미지로 만들기
    ```
   docker commit -a 'sohee' -m "my first commit" commit_test commit_test:first
   ```
   - commit 명령어: `docker commit [OPTIONS] CONTAINER [REPOSITORY:[TAG]]`
   - `-a`: 작성자를 나타내는 메타 데이터
   - `-m`: 커밋 메세지 (이미지 부가 설명)

3. commit_test:first 이미지로 새로운 컨테이너 만들기
    ```
   docker run -i -t --name commit_test2 commit_test:first
   ectho test_second >> second
   ```
   - commit_test2 에서는 commit_test:first에서 만들었던 first 파일이 실행된다!
   
### 2.3.2 이미지 구조 이해
- 이미지는 레이어로 구성된다.
- 이미지를 커밋할 때 컨테이너에서 변경된 사항만 새로운 레이어로 저장하고, 그 레이어를 포함해 새로운 이미지를 생성한다!

<img src="https://subicura.com/assets/article_images/2017-01-19-docker-guide-for-beginners-1/image-layer.png"/>
<br/><Br/>

1. commit_test:first 삭제하기
    ```
    docker rmi commit_test:first
   Error response from daemon: conflict: unable to remove repository reference "commit_test:first" (must force) - container 5da92564504e is using its referenced image 0f52b47507b6
   ```
    - 이미지를 사용중인 컨테이너가 존재할 경우, rmi 명령어로 삭제할 수 없다.

2. 이미지를 사용중인 컨테이너를 삭제한 뒤 이미지를 삭제
    ```
   docker stop commit_tes2 && docker rm commit_tes2
   commit_tes2
   commit_tes2
   
   docker rmi commit_test:first                    
   Untagged: commit_test:first
   ```
   - commit_test:first를 삭제했다고 해서 해당 이미지의 레이어 파일이 삭제되지는 않는다. 왜냐하면 commit_test:second가 존재하기 때문!
   - Untagged: --  : 실제 이미지 파일을 삭제하진 않고 레이어에 부여된 이름만 삭제한다.
   
3. commit_test:second 삭제하기
    ```
   docker rmi commit_test:second
   Untagged: commit_test:second
   Deleted: sha256:0c24ad55ab6c0e4e91927fdd51b7635c87308c0f1e1a235debdcd1e30c14afa3
   Deleted: sha256:fc83c6f88920c60e14a24b44cb46d8668ccd9cbe80dd160295bb4d3f7e717ca3
   Deleted: sha256:0f52b47507b66f021723bd8d38326731bf006d99b96f661f0081d91a4a0999dd
   Deleted: sha256:35dd117c50655ae88465dff79f64e44a2c93a229deadf007b30cf96904a3157e
   ``` 
   - Deleted: 이미지 레이어가 실제로 삭제됬음
 
 ### 2.3.3 이미지 추출
 ```
docker save -o ubuntu_14_04_.tar ubuntu:14.04
```  
- docker save 명령어로 이미지의 모든 데이터를 포함해 하나의 파일로 추출 할 수 있다.
- `-o`: 추출될 파일명

```
docker load -i ubuntu_14_04.tar
```
- docker load 명령어로 도커에 다시 로드할 수 있다.

   

 