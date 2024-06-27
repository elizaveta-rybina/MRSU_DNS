import { configureStore } from "@reduxjs/toolkit";
import domainReducer from "./slices/DomainSlice";
import recordReducer from "./slices/RecordSlice"

const store = configureStore({
  reducer: {
    domain: domainReducer,
    record: recordReducer,
  },
});

export default store;
