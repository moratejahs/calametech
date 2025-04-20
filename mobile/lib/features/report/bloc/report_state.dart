// part of 'report_bloc.dart';
//
// @immutable
// sealed class ReportState extends Equatable {
//   const ReportState();
//
//   @override
//   List<Object> get props => [];
// }
//
// final class ReportInitial extends ReportState {}
//
// final class ReportLoading extends ReportState {}
//
// final class ReportSuccess extends ReportState {
//   final String message;
//
//   const ReportSuccess(this.message);
//
//   @override
//   List<Object> get props => [message];
// }
//
// final class ReportError extends ReportState {
//   final String? message;
//   final Map<String, dynamic>? errors;
//
//   const ReportError({
//     this.message,
//     this.errors,
//   });
//
//   @override
//   List<Object> get props => [
//         if (message != null) message!,
//         if (errors != null) errors!,
//       ];
// }
