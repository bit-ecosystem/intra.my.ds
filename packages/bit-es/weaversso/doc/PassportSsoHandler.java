package com.api.sso.passport.service.impl;

import com.weaverboot.frame.ioc.anno.classAnno.WeaSsoIocComponent;
import com.weaverboot.frame.ioc.anno.methodAnno.WeaSsoIoc;
import com.weaverboot.frame.ioc.handler.replace.weaReplaceParam.impl.WeaSsoParam;

import weaver.conn.RecordSet;
import weaver.hrm.User;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WeaSsoIocComponent("passportSsoHandler")
public class PassportSsoHandler {

    @WeaSsoIoc(order = 1, description = "Passport (intra.my) SSO login")
    public void ssoLogin(WeaSsoParam p) {
        HttpServletRequest req = p.getRequest();
        HttpServletResponse resp = p.getResponse();

        String identity = req.getHeader("X-User-Email");
        if (identity == null || identity.isEmpty()) {
            identity = req.getParameter("email");
        }
        if (identity == null || identity.isEmpty()) {
            return;
        }

        RecordSet rs = new RecordSet();
        rs.executeQuery("SELECT id, loginid, lastname FROM HrmResource WHERE email = ?", identity);
        if (!rs.next()) {
            rs.executeQuery("SELECT id, loginid, lastname FROM HrmResource WHERE loginid = ?", identity);
            if (!rs.next()) return;
        }

        String userId   = rs.getString("id");
        String loginId  = rs.getString("loginid");
        String fullName = rs.getString("lastname");

        User user = new User();
        user.setUid(Integer.parseInt(userId));
        user.setLoginid(loginId);
        user.setLastname(fullName);

        // NOTE: Confirm the exact session attribute key your pages expect (e.g., via init_wev8.jsp)
        req.getSession(true).setAttribute("weaver_user", user);

        System.out.println("[SSO] Attached session for " + identity + " (uid=" + userId + ")");
    }
}
