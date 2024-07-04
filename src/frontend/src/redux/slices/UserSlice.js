import { createSlice } from "@reduxjs/toolkit";
import { mockDataUsers } from "../../data/mockData";

const initialState = {
  users: mockDataUsers,
  isFetch: false,
  isErr: false,
};

export const UserSlice = createSlice({
  name: "users",
  initialState,
  reducers: {
    deleteUsersSuccess(state, action) {
      state.isFetch = false;
      state.isErr = false;
      const arrIds = action.payload;
      state.users = state.users.filter((user) => !arrIds.includes(user.id));
    },
  },
});

export const { deleteUsersSuccess } = UserSlice.actions;
export default UserSlice.reducer;
