<div class="blueSolidBorderBox">
    <h1>ユーザーの設定 </h1>

    <div class="errors">


    </div>

    <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">

        <tbody>
            <tr>
                <th colspan="5">ユーザの写真</th>
            </tr>
            <tr>
                <td colspan="3">


                    <img src="user_images/members/original/19196_1607539604610.jpg?">
                    <form method="post" action="image-delete.do">
                        <input type="hidden" value="19196" name="userid">
                        <input type="submit" value="Delete Image">
                    </form>

                    <form method="post" action="image-upload.do" enctype="multipart/form-data">
                        <input type="hidden" value="19196" name="memberid">
                        <input type="file" name="upload">
                        <input type="submit" value="Upload" class="btnPink">

                        <input type="submit" value="Crop" class="btnPink">

                    </form>

                </td>
            </tr>
            <tr style="font-size: 12px;">
                <td>ファイルを選択」→ご自身の写真などを選択→「Upload]をクリック</td>
            </tr>
            <tr style="font-size: 12px;">
                <td>お勧め画像方式とサイズ　　JPG方式　サイズ100X100（75X75)</td>
            </tr>

        </tbody>
    </table>

    <br>
    <form action="changeMemberPassword.do" method="post" onsubmit="return MyForm.validate(this)">
        <input type="hidden" name="userid" value="19196">
        <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">
            <tbody>
                <tr>
                    <th colspan="5">変更パスワード</th>
                </tr>
                <tr valign="top">
                    <td width="130">現在のパスワード</td>
                    <td>:</td>
                    <td><input type="password" name="currentPassword" id="currpass*" alt="現在のパスワード" style="width: 200px;"></td>
                </tr>
                <tr valign="top">
                    <td>新しいパスワード</td>
                    <td>:</td>
                    <td><input type="password" name="newPassword" id="newpass*" alt="新しいパスワード" style="width: 200px;"></td>
                </tr>
                <tr valign="top">
                    <td>新しいパスワード<br>を再びタイプしなさい</td>
                    <td>:</td>
                    <td><input type="password" name="reTypedNewPassword" id="renewpass*" alt="新しいパスワードを再びタイプしなさい" style="width: 200px;"></td>
                </tr>
                <tr valign="top">
                    <td></td>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="変更パスワード" class="btnPink"></td>
                </tr>
            </tbody>
        </table>
    </form>

    <br>
    <form action="updateMemberSetting.do" method="post" onsubmit="return MyForm.validate(this)">
        <input type="hidden" name="userid" value="19196">
        <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">
            <tbody>
                <tr>
                    <th colspan="5">変更のユーザーの細部</th>
                </tr>
                <tr valign="top">
                    <td width="130">名</td>
                    <td>:</td>
                    <td><input type="text" name="firstname" id="fn*" alt="名" value="A _______   meeeeembeeerrrrr        royroy" style="width: 200px;"></td>
                </tr>
                <tr valign="top">
                    <td>名字</td>
                    <td>:</td>
                    <td><input type="text" name="lastname" id="ln*" alt="名字" value="A _______   meeeeembeeerrrrr        roy" style="width: 200px;"></td>
                </tr>


                <tr valign="top">
                    <td>電子メール</td>
                    <td>:</td>
                    <td>emailroy2002@yahoo.com<input type="hidden" name="email" value="emailroy2002@yahoo.com"></td>
                </tr>
                <tr valign="top">
                    <td>ページごとの項目</td>
                    <td>:</td>
                    <td>
                        <select name="itemsPerPage">
                            <option value="5">5&nbsp;&nbsp;</option>
                            <option value="10" selected="">10&nbsp;&nbsp;</option>
                            <option value="20">20&nbsp;&nbsp;</option>
                            <option value="50">50&nbsp;&nbsp;</option>
                            <option value="100">100&nbsp;&nbsp;</option>
                        </select>&nbsp;<em>ページあたりどのように多くの項目が表示されます。</em>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;</td>
                    <td><br><input type="submit" value="更新ユーザーの細部" class="btnPink"></td>
                </tr>
            </tbody>
        </table>
    </form>


</div>
