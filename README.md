# typecho附件七牛云上传

TODO:
七牛云文件管理
******

>七牛云提供文件存储,音频处理,文档转换,CDN等服务。    —— [官网](https://www.qiniu.com)

> 参考[七牛云存储使用指南]    —— [七牛云文档](http://developer.qiniu.com/) 

**使用方法**
上传至:/usr/plugins

安装后填写设置信息

上传附件

### 使用方法
```flow
st=>start: 上传至:/usr/plugins
e=>end
op=>operation: 启用插件
op=>operation: 填写配置信息,保存
op=>operation: 开始撰写文章体验附件上传

st->op->cond
cond(yes)->e
cond(no)->op
```

### 流程图
```flow
st=>start: Start
e=>end
op=>operation: My Operation
cond=>condition: Yes or No?

st->op->cond
cond(yes)->e
cond(no)->op
```


## 反馈与建议
- 微博：[@rain](http://weibo.com/u/771772666)
- 邮箱：<t0716@126.com>