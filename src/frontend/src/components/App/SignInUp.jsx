import { Route, Routes } from "react-router-dom";
import Topbar from "../../scenes/global/Topbar";
import SignInSide from "../../scenes/signin";
import SignUp from '../../scenes/signup';

function SignInUp() {
  return (
    <div className="app">
      <main className="content">
        <Topbar />
        <Routes>
          <Route path="/" element={<SignInSide />} />
          <Route path="/signup" element={<SignUp />} />
        </Routes>
      </main>
    </div>
  );
}

export default SignInUp;
