import { GridRowModes } from "@mui/x-data-grid";
import * as React from "react";
import { useNavigate } from "react-router-dom";

export const Utils = (rows, setRows, rowModesModel, setRowModesModel) => {
  // const navigate = useNavigate();

  // const handleButtonClick = (id) => {
  //   navigate(`/details/${id}`);
  // };

  const handleEditClick = (id) => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.Edit } });
  };

  const handleSaveClick = (id) => () => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.View } });
  };

  const handleDeleteClick = (id) => () => {
    setRows(rows.filter((row) => row.id !== id));
  };

  const handleCancelClick = (id) => () => {
    setRowModesModel({
      ...rowModesModel,
      [id]: { mode: GridRowModes.View, ignoreModifications: true },
    });

    const editedRow = rows.find((row) => row.id === id);
    if (editedRow.isNew) {
      setRows(rows.filter((row) => row.id !== id));
    }
  };

  const handleRowEditStop = (params, event) => {
    if (params.reason === GridRowEditStopReasons.rowFocusOut) {
      event.defaultMuiPrevented = true;
    }
  };

  const processRowUpdate = (newRow) => {
    const updatedRow = { ...newRow, isNew: false };
    setRows(rows.map((row) => (row.id === newRow.id ? updatedRow : row)));
    return updatedRow;
  };

  const handleRowModesModelChange = (newRowModesModel) => {
    setRowModesModel(newRowModesModel);
  };

  return {
    handleEditClick,
    handleSaveClick,
    handleDeleteClick,
    handleCancelClick,
    handleRowEditStop,
    processRowUpdate,
    handleRowModesModelChange,
    handleButtonClick,
  };
};
