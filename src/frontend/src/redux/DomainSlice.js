import { createSlice } from "@reduxjs/toolkit";
import { mockDataDomain } from '../data/mockData';

const initialState = {
  domains: mockDataDomain,
  isFetch: false,
  isErr: false,
};

export const DomainSlice = createSlice({
  name: "domains",
  initialState,
  reducers: {
    deleteDomainsSuccess(state, action) {
      state.isFetch = false;
      state.isErr = false;
      const arrIds = action.payload;
      state.domains = state.domains.filter(
        (domain) => !arrIds.includes(domain.id)
      );
    },
  },
});

export const { deleteDomainsSuccess } = DomainSlice.actions;
export default DomainSlice.reducer;
