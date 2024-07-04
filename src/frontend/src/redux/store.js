import { configureStore } from "@reduxjs/toolkit";
import domainReducer from "./slices/DomainSlice";
import recordReducer from "./slices/RecordSlice";
import userReducer from "./slices/UserSlice";

const store = configureStore({
  reducer: {
    domain: domainReducer,
    record: recordReducer,
    user: userReducer,
  },
});

export default store;
