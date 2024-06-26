import { configureStore } from "@reduxjs/toolkit";
import domainReducer from "./DomainSlice";

const store = configureStore({
  reducer: {
    domain: domainReducer,
  },
});

export default store;
